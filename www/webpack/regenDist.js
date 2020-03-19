/**
 *
 * @param webpackConfig An Encore config object
 * @constructor
 */
exports.RegenDist = function (webpackConfig) {
	const fs = require("fs");
	fs.writeFile('./webpack.config.dist.js', 'module.exports = ' + JSON.stringify(webpackConfig), function (err) {
		if (err) {
			throw new Error(err);
		}
		console.info('[ ---- webpack.config.dist written ----]\r');
	});
}