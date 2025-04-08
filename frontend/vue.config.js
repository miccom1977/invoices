// vue.config.js
const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    port: 8065,
  },
  chainWebpack: config => {
    config.plugin('define').tap(args => {
      const [definitions] = args
      
      definitions.__VUE_OPTIONS_API__ = JSON.stringify(true)
      definitions.__VUE_PROD_DEVTOOLS__ = JSON.stringify(false)
      
      return args
    })
  }
})
