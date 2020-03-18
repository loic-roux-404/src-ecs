var Encore = require("@symfony/webpack-encore")
const path = require("path")
const fsr = require("fs").readFileSync
const yml = require("js-yaml")

const {
	parameters : {manifestKey , configs , poll, regenDist }
} = yml.safeLoad(fsr("./config/pages.yml"), yml.JSON_SCHEMA)

const EXCLUDE = ["/node_modules/", "/vendor/"]

// Config message
console.info("Don't forget to create pages necessary files,\n check config/pages.yaml\n")
// end

// set an config object used in all workspace (front-office / admin)
const aliases = function aliases(config, alias = null) {
	let configAliases = {};
	const name = config.name,
		aliasName = alias ? alias : name.slice(0, 2);

	try {
		configAliases[`@${aliasName}`] = path.resolve(__dirname,`assets/${name}/scripts`)
		configAliases[`#${aliasName}`] =  path.resolve(__dirname, `assets/${name}/scss`)
		configAliases['@core'] = path.resolve(__dirname, `assets/core/ts`)
		configAliases['#core'] = path.resolve(__dirname, `assets/core/scss`)
	} catch (e) {
		throw new Error("Pages name are probably too similar, verify your config/pages.yaml : \n" + e)
	}

	return {
		// add to all workspaces
		...config.resolve.alias, ...configAliases,
		...config.resolve.extensions.push(".scss",".ts")
	}
}

const getWorkspaces = function getWorkspaces(entry = null) {

	let workspaces = []

	for (const name in configs) {
		const { build, pages, alias, sharedEntry } = configs[name]
		const buildPath = build ? '/'+build :`/${name}/build`;

		if (typeof entry === "string") {
			Encore.addEntry(entry, path.resolve(__dirname, `assets/${name}/${entry}`))
		} else {
				pages ? pages.forEach(function(entry) {
				if (typeof entry === "string") {
					Encore.addEntry(entry, path.resolve(__dirname, `assets/${name}/${entry}`))
				} else {
					throw new Error("There is a syntax error in config/pages.yaml\n")
				}
			}): null;
		}

		Encore

			.setOutputPath(`${manifestKey}/${buildPath}`)

			.setManifestKeyPrefix(manifestKey)

			.setPublicPath(`${buildPath}`)

			.enableSingleRuntimeChunk()

			.enableBuildNotifications(!Encore.isProduction())

			.enableSourceMaps(!Encore.isProduction())

			.enableVersioning(Encore.isProduction())

			.autoProvidejQuery()

			.enableSassLoader(options => {
				options.implementation = require('sass');
			})

			.enablePostCssLoader()

			.cleanupOutputBeforeBuild([])

			.splitEntryChunks()

			.configureLoaderRule('javascript', loader => {
				loader.test = /.(j|t)sx?$/;
			})

		;

		let config = Encore.getWebpackConfig()

		// fix build with nfs enable
		Encore.configureWatchOptions(watchOptions => {
			watchOptions.poll = poll; // check for changes every 250 milliseconds
			watchOptions.ignored = EXCLUDE;
		});

		config.name = name
		config.resolve.alias = aliases(config, alias ? alias : null)
		workspaces.push(config);

		Encore.reset()
	}

	if(!Encore.isProduction() && regenDist) {
		const fs = require("fs");
		fs.writeFile("./webpack.config.dist.json", "module.exports = "+JSON.stringify(workspaces[0]), function(err) {
			if(err) {
				throw new Error(err);
			}
			console.info('------------\nwebpack.config.dist written');
		});
	}

	return workspaces
};

module.exports = (env, args) => {
	console.log(args)
	return getWorkspaces(args.entry);
};
