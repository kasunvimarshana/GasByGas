import path from 'path';
import fs from 'fs';

/**
 * Plugin to copy image assets from the resources directory to the public directory after build.
 */
const copyImagesPostBuildPlugin = () => {
    return {
        name: 'copy-images-post-build',
        closeBundle() {
            const sourceImagesPath = path.resolve(__dirname, 'resources/img');
            const destinationImagesPath = path.resolve(__dirname, 'public/img');

            try {
                // Ensure the destination directory exists
                if (!fs.existsSync(destinationImagesPath)) {
                    fs.mkdirSync(destinationImagesPath, { recursive: true });
                    console.log('📁 Destination directory created: public/img');
                }

                // Copy images from source to destination
                fs.cpSync(sourceImagesPath, destinationImagesPath, { recursive: true });
                console.log('🖼️ Images successfully copied from resources/img to public/img');
                console.log('✅ Post-build tasks completed successfully.');
            } catch (error) {
                console.error('❌ Error during post-build image copying:', error);
            }
        }
    };
};

export default copyImagesPostBuildPlugin;
