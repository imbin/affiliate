const nodeExternals = require('webpack-node-externals')

module.exports = {
  // ...
  externals: [nodeExternals()],

  devtool: 'inline-cheap-module-source-map',
  output: {
    // ...
    // 在源码表中使用绝对路径 (对于在 IDE 中调试时很重要)
    devtoolModuleFilenameTemplate: '[absolute-resource-path]',
    devtoolFallbackModuleFilenameTemplate: '[absolute-resource-path]?[hash]'
  }
}