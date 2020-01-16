const shell = require('shelljs')
exports.performance = () => {
    shell.exec('./performance-testing.sh')
};