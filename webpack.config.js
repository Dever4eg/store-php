const path = require('path');

module.exports = {
    entry: './resources/assets/js/index.js',
    output: {
        filename: 'app.js',
        path: path.resolve(__dirname, 'public/assets')
    },
};
