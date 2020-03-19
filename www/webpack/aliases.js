// set an config object used in all workspace (front-office / admin)
exports.Aliases = {
	savePreviousAliases: {},
	/**
	 *
	 * @param config
	 * @param alias
	 * @param pathResolve keep function in memory by using it as param
	 * @returns Object Alias webpack config part
	 */
	generateAliases(config, alias = null) {
		const configAliases = {}
		const name = config.name,
				aliasName = alias ? alias : name.slice(0, 2);

		console.info(`[---- Create ${name} alias : ${aliasName} ----]\r`)

		try {
			configAliases[`@${aliasName}`] = path.resolve(dirname, `assets/${name}/scripts`)
			configAliases[`#${aliasName}`] = path.resolve(dirname, `assets/${name}/scss`)
			configAliases['@core'] = path.resolve(dirname, `assets/core/ts`)
			configAliases['#core'] = path.resolve(dirname, `assets/core/scss`)
		} catch (e) {
			throw new Error("Pages name are probably too similar, verify your config/pages.yaml : \n" + e)
		}

		this.savePreviousAliases = {...configAliases, ...this.savePreviousAliases }

		return  {
				// add to all workspaces
				...config.resolve.alias, ...this.savePreviousAliases,
				...config.resolve.extensions.push(".scss", ".sass", ".css")
			}
		}
	}
