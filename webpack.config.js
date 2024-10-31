const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { merge } = require('webpack-merge');

const defaultRules = defaultConfig.module.rules.filter((rule) => {
    return String(rule.test) !== String(/\.(sc|sa)ss$/);
});

const commonConfig = {
    entry: {
        index: path.resolve(process.cwd(), 'src', 'js', 'index.js'),
        'ringcaptcha-block': path.resolve(process.cwd(), 'src', 'js', 'ringcaptcha-block', 'index.js'),
        'ringcaptcha-block-frontend': path.resolve(process.cwd(), 'src', 'js', 'ringcaptcha-block', 'frontend.js'),
    },
    module: {
        rules: [
            ...defaultRules,
            {
                test: /\.(sc|sa)ss$/,
                exclude: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-loader', options: { importLoaders: 1 } },
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                includePaths: ['src/css'],
                            },
                        },
                    },
                ],
            },
        ],
    },
    output: {
        path: path.resolve(__dirname, 'build'),
        filename: '[name].js',
    },
    plugins: [
        new WooCommerceDependencyExtractionWebpackPlugin(),
        new MiniCssExtractPlugin({
            filename: `[name].css`,
        }),
        new CleanWebpackPlugin(), // Clean the build folder before each build
    ],
};

const developmentConfig = {
    mode: 'development',
    devtool: 'source-map',
};

const productionConfig = {
    mode: 'production',
    optimization: {
        minimize: true,
        minimizer: [new TerserPlugin()],
    },
};

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    const envConfig = isProduction ? productionConfig : developmentConfig;
    return merge(defaultConfig, commonConfig, envConfig);
};