#!/bin/bash

# =============================================================================
# Dev Sync Script - Watch and sync changes to dist/ for Docker development
# =============================================================================

set -e

PLUGIN_SLUG="my-plugin"
BUILD_DIR="dist"
DEST_DIR="$BUILD_DIR/$PLUGIN_SLUG"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info() { echo -e "${GREEN}[SYNC]${NC} $1"; }
log_watch() { echo -e "${BLUE}[WATCH]${NC} $1"; }
log_warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }

# Files and directories to sync
SYNC_FILES=(
    "${PLUGIN_SLUG}.php"
    "uninstall.php"
    "readme.txt"
)

SYNC_DIRS=(
    "src"
    "assets/css"
    "assets/js"
    "assets/images"
    "languages"
    "vendor"
)

# Initial build/sync
do_initial_sync() {
    log_info "Initial sync to $DEST_DIR..."

    # Create dest directory
    mkdir -p "$DEST_DIR"

    # Sync files
    for file in "${SYNC_FILES[@]}"; do
        if [ -f "$file" ]; then
            cp "$file" "$DEST_DIR/"
            log_info "  Copied $file"
        fi
    done

    # Sync directories
    for dir in "${SYNC_DIRS[@]}"; do
        if [ -d "$dir" ]; then
            mkdir -p "$DEST_DIR/$(dirname $dir)"
            rsync -a --delete "$dir/" "$DEST_DIR/$dir/"
            log_info "  Synced $dir/"
        fi
    done

    log_info "Initial sync complete!"
}

# Sync a single file/directory
sync_path() {
    local path="$1"

    # Check if it's a file we care about
    for file in "${SYNC_FILES[@]}"; do
        if [[ "$path" == "$file" ]]; then
            cp "$path" "$DEST_DIR/"
            log_watch "Synced: $path"
            return
        fi
    done

    # Check if it's in a directory we care about
    for dir in "${SYNC_DIRS[@]}"; do
        if [[ "$path" == "$dir"* ]]; then
            if [ -f "$path" ]; then
                mkdir -p "$DEST_DIR/$(dirname $path)"
                cp "$path" "$DEST_DIR/$path"
                log_watch "Synced: $path"
            elif [ -d "$path" ]; then
                rsync -a --delete "$path/" "$DEST_DIR/$path/"
                log_watch "Synced dir: $path"
            fi
            return
        fi
    done
}

# Watch for changes using fswatch (macOS) or inotifywait (Linux)
do_watch() {
    log_info "Starting file watcher..."
    log_info "Watching: ${SYNC_FILES[*]} ${SYNC_DIRS[*]}"
    echo ""

    # Build watch paths
    local watch_paths=()
    for file in "${SYNC_FILES[@]}"; do
        [ -f "$file" ] && watch_paths+=("$file")
    done
    for dir in "${SYNC_DIRS[@]}"; do
        [ -d "$dir" ] && watch_paths+=("$dir")
    done

    if command -v fswatch &> /dev/null; then
        # macOS with fswatch
        fswatch -o "${watch_paths[@]}" | while read -r; do
            # On change, do a full sync of changed directories
            for dir in "${SYNC_DIRS[@]}"; do
                if [ -d "$dir" ]; then
                    rsync -a --delete "$dir/" "$DEST_DIR/$dir/" 2>/dev/null
                fi
            done
            for file in "${SYNC_FILES[@]}"; do
                if [ -f "$file" ]; then
                    cp "$file" "$DEST_DIR/" 2>/dev/null
                fi
            done
            log_watch "Changes synced at $(date +%H:%M:%S)"
        done
    elif command -v inotifywait &> /dev/null; then
        # Linux with inotifywait
        inotifywait -m -r -e modify,create,delete,move "${watch_paths[@]}" |
        while read -r directory events filename; do
            sync_path "${directory}${filename}"
        done
    else
        log_warn "No file watcher found. Install fswatch (macOS) or inotify-tools (Linux)"
        log_warn "Running in poll mode (checking every 2 seconds)..."

        # Fallback: poll mode
        while true; do
            sleep 2
            for dir in "${SYNC_DIRS[@]}"; do
                if [ -d "$dir" ]; then
                    rsync -a --delete "$dir/" "$DEST_DIR/$dir/" 2>/dev/null
                fi
            done
            for file in "${SYNC_FILES[@]}"; do
                if [ -f "$file" ]; then
                    cp "$file" "$DEST_DIR/" 2>/dev/null
                fi
            done
        done
    fi
}

# Main
main() {
    echo ""
    echo "=========================================="
    echo "  Dev Sync - WordPress Plugin Development"
    echo "=========================================="
    echo ""

    # Initial sync
    do_initial_sync

    echo ""
    log_info "Press Ctrl+C to stop watching"
    echo ""

    # Start watching
    do_watch
}

main
