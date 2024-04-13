# #!/bin/bash

# # Create directories
# mkdir -p admin/css admin/js admin/partials includes languages

# # Create files
# touch admin/css/admin-style.css admin/js/admin-scripts.js admin/partials/admin-page.php
# touch includes/class-menu-shortcode.php includes/helper-functions.php
# touch languages/menu-shortcode-plugin.pot menu-shortcode-plugin.php README.md

#!/bin/bash

# Define the output file
output_file="tree.out.txt"

# Function to recursively list files and their contents
list_files() {
    local file
    for file in "$1"/*; do
        if [ -d "$file" ]; then
            echo "$file:" >> "$output_file"
            list_files "$file"
        else
            echo "File: $file" >> "$output_file"
            echo "Content:" >> "$output_file"
            cat "$file" >> "$output_file"
            echo "" >> "$output_file"
        fi
    done
}

# Start listing files from the current directory
echo "File structure and contents:" > "$output_file"
list_files .

echo "File structure and contents saved to $output_file"
