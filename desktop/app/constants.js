const ROOT_FOLDER_PATH = __dirname + '/.'; //TODO-SWITCH-PROD
//const ROOT_FOLDER_PATH = __dirname + '/..'; //TODO-SWITCH-DEV
const DIST_FOLDER_PATH = ROOT_FOLDER_PATH + '/dist';

module.exports = {
    ROOT_FOLDER_PATH: ROOT_FOLDER_PATH,
    DIST_FOLDER_PATH: DIST_FOLDER_PATH,
    GATEWAY_URL: 'https://scoreboard.padbol.com'
    //GATEWAY_URL: 'http://localhost:8000'
};