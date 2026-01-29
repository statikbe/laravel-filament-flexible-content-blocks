## Package development

### Setup

1. run `nvm use` to make sure you're on the desire node version
    * or `nvm install` when you don't have that version yet
2. run `npm install` to install the latest node_modules


### Releasing a new version of this package

* When you made changes to some typescript assets, do the following steps **before** making the new release version:
    * run `npm run build` to build the needed assets within the `/resources/dist` folder
    * commit the generated `/resources/dist` asset files
