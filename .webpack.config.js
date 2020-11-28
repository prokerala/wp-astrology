const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const ErrorNotification = require("webpack-error-notification");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

module.exports = {
	context: __dirname,
	entry: {
		'js/admin/settings': ['./assets/src/js/admin/settings/app.js'],
		'css/admin/settings': ['./assets/src/scss/admin/settings.scss'],
		'js/admin/block': './assets/src/js/admin/block.js',
		'js/main': ['./assets/src/js/front/main/app.js' ],
		'css/main': './assets/src/scss/main.scss',
	},
	output: {
		path: path.resolve(__dirname, 'assets/dist'),
		filename: '[name].js'
	},
	mode: 'development',
	devtool: 'eval-cheap-source-map',
	module: {
		rules: [
			{
				enforce: 'pre',
				exclude: /node_modules/,
				test: /\.jsx$/,
				loader: 'eslint-loader'
			},
			{
				test: /\.jsx?$/,
				loader: 'babel-loader'
			},
			{
				test: /\.[ps]?css$/,
				use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader']
			},
			{
				test: /\.(jpe?g|png|gif)\$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							outputPath: 'images/',
							name: '[name].[ext]'
						}
					},
					'img-loader'
				]
			}
		]
	},
	plugins: [
		new StyleLintPlugin(),
		new MiniCssExtractPlugin({
			filename: '[name].css'
		}),
		new ErrorNotification(),
		new CleanWebpackPlugin(),
		new ESLintPlugin({
			cache: true,
			// formatter: require.resolve('react-dev-utils/eslintFormatter'),
			eslintPath: require.resolve('eslint'),
			resolvePluginsRelativeTo: __dirname,
			ignore: true,
			useEslintrc: true,
			extensions: ['js', 'jsx', 'ts', 'tsx'],
			fix: true,
		})
	],
	optimization: {
		minimizer: [new UglifyJsPlugin(), new OptimizeCssAssetsPlugin()]
	}
};
