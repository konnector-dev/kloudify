const shell = require('shelljs')
exports.performance = (context) => {
    shell.exec('./performance-testing.sh')
};