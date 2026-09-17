#!/usr/bin/env bash
#
# .ploi/deploy-wp.sh — server-side installer for iHowz plugin/theme zip artifacts.
#
# The site directory on Pinot is NOT a git checkout (WordPress core, uploads and
# site content live here), so unlike the Laravel apps CI cannot "git pull" a
# revision onto the server. Instead each deploy scp's this script plus the
# built zip and invokes it:
#
#   bash deploy-wp.sh <plugin|theme> <zip-path> <sha> <version> <wp-root>
#
# It verifies the artifact carries the expected version header BEFORE touching
# the live directory, swaps the installed directory in with a timestamped
# backup kept OUTSIDE the web root (a backup dir inside wp-content would be
# picked up by WordPress as a duplicate plugin, and by nginx as a public file
# tree), re-checks activation via wp-cli where available, and writes a marker
# file that the "Verify deployed code" CI step asserts against (did the
# intended commit actually land?).
#
# OPcache needs no manual reload here: production runs with
# opcache.validate_timestamps=0 but ihowz.php self-resets OPcache whenever the
# plugin file's mtime changes (see the comment block at the top of ihowz.php).

set -euo pipefail

TYPE="${1:?usage: deploy-wp.sh <plugin|theme> <zip> <sha> <version> <wp-root>}"
ZIP="${2:?missing zip path}"
SHA="${3:?missing commit sha}"
VERSION="${4:?missing expected version}"
WP_ROOT="${5:?missing WordPress root}"

case "$TYPE" in
    plugin|theme) ;;
    *) echo "ERROR: type must be 'plugin' or 'theme', got '$TYPE'" >&2; exit 2 ;;
esac

[ -f "$ZIP" ] || { echo "ERROR: artifact $ZIP not found" >&2; exit 1; }
[ -d "$WP_ROOT/wp-content" ] || { echo "ERROR: $WP_ROOT does not look like a WordPress root" >&2; exit 1; }

SLUG="ihowz"
if [ "$TYPE" = "theme" ]; then
    INSTALL_ROOT="$WP_ROOT/wp-content/themes"
    MARKER_SOURCE_REL="style.css"
    VERSION_AWK='/^Version:/ {print $2; exit}'
else
    INSTALL_ROOT="$WP_ROOT/wp-content/plugins"
    MARKER_SOURCE_REL="ihowz.php"
    VERSION_AWK='/^ \* Version:/ {print $2; exit}'
fi
INSTALL_DIR="$INSTALL_ROOT/$SLUG"

# State lives one level above the WordPress root — outside the web server's
# document root, and never destroyed by a directory swap.
STATE_ROOT="$(dirname "$WP_ROOT")"
MARKER_DIR="$STATE_ROOT/.ihowz-pipeline"
MARKER_FILE="$MARKER_DIR/$TYPE.marker"
BACKUP_ROOT="$STATE_ROOT/.ihowz-backups/$TYPE"

command -v unzip >/dev/null 2>&1 || { echo "ERROR: unzip is not installed on this server" >&2; exit 1; }

# Extract beside the target (same filesystem → the swap below is a rename).
# Dot-prefixed so WordPress' plugin/theme scanner skips it while it exists.
TMP="$(mktemp -d "$INSTALL_ROOT/.deploy-$TYPE.XXXXXX")"
trap 'rm -rf "$TMP"' EXIT
unzip -q "$ZIP" -d "$TMP"
[ -d "$TMP/$SLUG" ] || { echo "ERROR: artifact did not contain a $SLUG/ directory" >&2; exit 1; }

# Verify the artifact is the version we were told to deploy, BEFORE swapping.
ACTUAL_VERSION="$(awk -F': ' "$VERSION_AWK" "$TMP/$SLUG/$MARKER_SOURCE_REL")"
if [ "$ACTUAL_VERSION" != "$VERSION" ]; then
    echo "ERROR: artifact version mismatch — zip declares '$ACTUAL_VERSION', expected '$VERSION'." >&2
    echo "Refusing to deploy; the release tag, Version header and built artifact must agree." >&2
    exit 1
fi

TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP=""
if [ -d "$INSTALL_DIR" ]; then
    mkdir -p "$BACKUP_ROOT"
    BACKUP="$BACKUP_ROOT/$TIMESTAMP"
    mv "$INSTALL_DIR" "$BACKUP"
    # Keep the five most recent backups; prune the rest.
    ls -1dt "$BACKUP_ROOT"/* 2>/dev/null | tail -n +6 | xargs -r rm -rf
fi

mv "$TMP/$SLUG" "$INSTALL_DIR"
find "$INSTALL_DIR" -type d -exec chmod 755 {} +
find "$INSTALL_DIR" -type f -exec chmod 644 {} +

rollback() {
    echo "ERROR: deploy failed — restoring $BACKUP" >&2
    rm -rf "$INSTALL_DIR"
    if [ -n "$BACKUP" ]; then
        mv "$BACKUP" "$INSTALL_DIR"
    fi
    exit 1
}

# wp-cli checks. Absent wp-cli is tolerated (the marker file still proves the
# swap); a failing ACTIVATION is not, for plugins.
if command -v wp >/dev/null 2>&1; then
    if [ "$TYPE" = "plugin" ]; then
        if ! (cd "$WP_ROOT" && wp plugin is-active "$SLUG" >/dev/null 2>&1); then
            # First install on a fresh site (staging), or the plugin was
            # manually deactivated.
            if ! (cd "$WP_ROOT" && wp plugin activate "$SLUG" >/dev/null 2>&1); then
                rollback
            fi
        fi
        (cd "$WP_ROOT" && wp rewrite flush >/dev/null 2>&1) || true
    else
        # Never switch the active theme from CI — that is an editorial choice.
        (cd "$WP_ROOT" && wp theme list --status=active --format=count) || true
    fi
    (cd "$WP_ROOT" && wp cache flush >/dev/null 2>&1) || true
fi

mkdir -p "$MARKER_DIR"
{
    echo "type=$TYPE"
    echo "version=$ACTUAL_VERSION"
    echo "sha=$SHA"
    echo "deployed_at=$(date -u +%Y-%m-%dT%H:%M:%SZ)"
    if [ -n "$BACKUP" ]; then
        echo "backup=$BACKUP"
    fi
} > "$MARKER_FILE"

echo "Deployed $TYPE $ACTUAL_VERSION (sha ${SHA:0:12}) to $INSTALL_DIR"
if [ -n "$BACKUP" ]; then
    echo "Previous version kept as: $BACKUP"
fi
