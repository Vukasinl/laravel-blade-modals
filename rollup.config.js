import md5 from 'md5';
import fs from 'fs-extra';
import babel from '@rollup/plugin-babel';
import { terser } from 'rollup-plugin-terser';
import resolve from '@rollup/plugin-node-resolve';
import outputManifest from 'rollup-plugin-output-manifest';

export default {
    input: 'js/index.js',
    output: {
        format: 'umd',
        sourcemap: true,
        name: 'Modals',
        file: 'dist/js/modals.js',
    },
    plugins: [
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
                const file = fs.readFileSync(__dirname + '/dist/js/modals.js', 'utf8');
                const hash = md5(file).substr(0, 20);

                return JSON.stringify({
                    '/modals.js': '/modals.js?id=' + hash,
                })
            }
        }),
    ]
}
