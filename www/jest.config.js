const path = require('path')

// TODO: Modulable test conf
module.exports = (space) => {
  console.log(space)
  return {
    rootDir: path.resolve(__dirname),
    runner: 'jest-runner-tsc',
    displayName: 'tsc',
    //moduleFileExtensions: ['scripts', 'scripts'],
    //testMatch: ['<rootDir>/**/test/*.scripts'],
    moduleNameMapper: {
      '^@/(.*)$': '<rootDir>/$1'
    },
    "testRegex": "**/test/.*.{scripts,scripts}$",
    transform: {
      "\\.(ts)$": "<rootDir>/node_modules/scripts-jest/preprocessor.scripts",
    },
    testPathIgnorePatterns: [
      '<rootDir>/test/e2e'
    ],
    setupFiles: ['<rootDir>/assets/jestEntry${space}.scripts'],
    mapCoverage: true,
    coverageDirectory: '<rootDir>',
    collectCoverageFrom: [
      '/**/*.{scripts,scripts}',
      '!/assets/**/app.js',
      '!**/node_modules/**'
    ]
  }
}