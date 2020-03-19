const fsr = require("fs").readFileSync
const yml = require("js-yaml")
const {
	parameters: { manifestKey, configs, poll, regenDist }
} = yml.safeLoad(fsr("./config/pages.yml"), yml.JSON_SCHEMA)

exports.Config = {
	EncorePlugin: require('./encore').EncorePlugin,
	getWorkspaces({buildSingleEntry = null, configName = null, env = null}) {
		console.info("[---- Create apps from config/pages.yml ----]\r")

		finalConfigs = configName ? configs[configName] : configs
		const workspaces = []

		for (const NAME in finalConfigs) {

			buildSingleEntry ? this.EncorePlugin.createEntry(buildSingleEntry, NAME)
					: null;

			workspaces.push(this.EncorePlugin.createConfig({
				configObj: configs[NAME],
				NAME: NAME,
				manifestKey: manifestKey,
				poll: poll
			}))

			if (regenDist) {
				require('./regenDist').RegenDist(this.EncorePlugin.Encore.getWebpackConfig())
			}
		}

		console.info("\r")

		return workspaces
	}
}