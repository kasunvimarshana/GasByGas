const fs = require('fs');
const path = require('path');

// Directories to copy
const dirsToCopy = [
    { src: 'resources/fonts', dest: 'public/fonts' },
    { src: 'resources/images', dest: 'public/images' },
];

// Copy directory function
function copyDir(src, dest) {
    if (!fs.existsSync(dest)) {
        fs.mkdirSync(dest, { recursive: true });
    }

    const files = fs.readdirSync(src);
    files.forEach((file) => {
        const srcFile = path.join(src, file);
        const destFile = path.join(dest, file);

        if (fs.lstatSync(srcFile).isDirectory()) {
            copyDir(srcFile, destFile); // Recurse into subdirectories
        } else {
            fs.copyFileSync(srcFile, destFile); // Copy file
        }
    });
}

// Copy each directory
dirsToCopy.forEach(({ src, dest }) => {
    if (fs.existsSync(src)) {
        copyDir(src, dest);
        console.log(`Copied: ${src} to ${dest}`);
    } else {
        console.log(`Directory not found: ${src}`);
    }
});
