global.path = require("path")
global.dirname = __dirname;

module.exports = (env, args) => (require('./webpack/workspaces').Config.getWorkspaces({
	entry: args.entry,
	config: args.configName,
	env: env
}))
