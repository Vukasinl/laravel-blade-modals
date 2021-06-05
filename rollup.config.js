import md5 from 'md5';
import fs from 'fs-extra';
import babel from '@rollup/plugin-babel';
import { terser } from 'rollup-plugin-terser';
import resolve from '@rollup/plugin-node-resolve';
import scss from 'rollup-plugin-scss';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import outputManifest from 'rollup-plugin-output-manifest';

export default {
    input: {
        'js/modals': 'js/index.js'
    },
    output: {
        format: 'umd',
        sourcemap: true,
        name: 'Modals',
        dir: 'dist',
    },
    plugins: [
        scss({
            output: 'dist/css/modals.css',
            outputStyle: "compressed",
            // Run postcss processor before output
            processor: css => postcss([autoprefixer()]),
        }),
        resolve(),
        terser({
            mangle: false,
            compress: {
                drop_debugger: false,
            },
        }),
        babel({
            exclude: 'node_modules/**'
        }),
        // Mimic Laravel Mix's mix-manifest file for auto-cache-busting.
        outputManifest({
            serialize() {
                const script = fs.readFileSync(__dirname + '/dist/js/modals.js', 'utf8');
                const scriptHash = md5(script).substr(0, 20);

                const styles = fs.readFileSync(__dirname + '/dist/css/modals.css', 'utf8');
                const stylesHash = md5(styles).substr(0, 20);

                return JSON.stringify({
                    '/modals.js': '/modals.js?id=' + scriptHash,
                    '/modals.css': '/modals.css?id=' + stylesHash
                })
            }
        })
    ]
}
