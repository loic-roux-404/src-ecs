exports.EncorePlugin = {
	EXCLUDE: ["/node_modules/", "/vendor/"],
	Encore: require("@symfony/webpack-encore"),
	aliases: require('./aliases').Aliases,
	/*
	 * @param configObj {
	 * 		build: "The build full path without manifestKey",
	 * 		pages: "Array of entries corresponding to an app",
	 * 		alias: "set an common alias used in import with '@' for js '#' for css",
	 * }
	 * @param NAME The config name
	 * @param manifestKey Set the parent directory name of builded configs
	 * @param poll Webpack watch polling settings
	 */
	createConfig({configObj, NAME, manifestKey, poll}) {
		console.info(`[---- Create config for ${NAME} ----]\r`)

		const { build, pages, alias } = configObj
		const buildPath = build ? '/' + build : `/${NAME}/build`;
		pages ? pages.forEach(entry => this.createEntry(entry, NAME)) : null;

		this.Encore

				.enableSingleRuntimeChunk()

				.setOutputPath(`${manifestKey}/${buildPath}`)

				.setManifestKeyPrefix(manifestKey)

				.setPublicPath(`${buildPath}`)

				.cleanupOutputBeforeBuild()

				.enableBuildNotifications()

				.enableSourceMaps(!this.Encore.isProduction())

				.enableVersioning(this.Encore.isProduction())

				.autoProvidejQuery()

				.enableSassLoader(options => {
					options.implementation = require('sass');
				})

				.enablePostCssLoader()

				.splitEntryChunks()

				.addLoader({ test: /\.ts$/, loader: 'babel-loader' })
		;

		const config = this.Encore.getWebpackConfig()

		// fix build with nfs enable
		this.Encore.configureWatchOptions(watchOptions => {
			watchOptions.poll = poll // check for changes every 250 milliseconds
			watchOptions.ignored = this.EXCLUDE
		});

		config.name = NAME
		config.resolve.alias = this.aliases.generateAliases(config, alias)
		this.Encore.reset()

		return config
	},
	/**
	 *
	 * @param entry A string
	 * @param name
	 */
	createEntry(entry, name) {
		if (typeof entry === "string") {
			this.Encore.addEntry(entry, path.resolve(dirname, `assets/${name}/${entry}`))
		} else {
			throw new Error("There is a syntax error in config/pages.yaml\n")
		}
	},
}