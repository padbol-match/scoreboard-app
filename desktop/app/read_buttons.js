const constants = require('./constants.js');
const log = require('electron-log');
const request = require('request');

let lastPressedTime = 0;
let pressedAlreadySent = true;

async function readButtons(data, buttonCodes, mainWindow){
  try{
    const now = new Date();
    const localStorage = await mainWindow.webContents.executeJavaScript('localStorage');
    
    if(Math.abs(lastPressedTime - now) > 1000 && pressedAlreadySent){
      pressedAlreadySent = false;
      
      mainWindow.webContents.send('REMOTE_CONTROL_PRESSED', {button: data});
      log.info("Button Codes", buttonCodes, data);
      let correctButtonCode = false;
      
      for(const field in buttonCodes){
        buttonCodes[field].forEach((buttonCode, index) => {
          if(data == buttonCode){
            let newIndex = index+1;

            if(newIndex == 1 || newIndex == 2){
              sendPoint(field, newIndex, localStorage);
            }else if(newIndex == 3){
              sendBackPoint(field, newIndex, localStorage);
            }

            correctButtonCode = true;
          }
        });
      };

      buttonCodes = await getButtonCodes(localStorage);

      if(!correctButtonCode){
        pressedAlreadySent = true;
      }
    }
    
    mainWindow.webContents.send('REMOTE_CONTROL_SAVING', {button: data});
  } catch(error) {
    log.error("Error:", error);
    return false;
  }
}

function getButtonCodes(localStorage){
  log.info("getButtonCodes: ", localStorage);

  return new Promise(function (resolve, reject) {
    request({
      //ca: [certFile],
      rejectUnauthorized: false,
      url: constants.GATEWAY_URL + "/api/device/get-button-codes",
      method: "POST",
      form: {
        "tenant": localStorage['scoreboard-tenant'],
      }
    }, function (error, response, body){
      if (!error && response.statusCode == 200) {
        resolve(JSON.parse(body));
      } else {
        log.error("Error request getButtonCodes:", error, response.statusCode);
        reject(error);
      }
    });
  }).catch((error) => {
    log.error("Error Promise getButtonCodes:", arguments);
  });
}

function sendPoint(field, team, localStorage){
  log.info("sendPoint: ", field, team, localStorage);

  return new Promise(function (resolve, reject) {
    request({
      //ca: [certFile],
      rejectUnauthorized: false,
      url: constants.GATEWAY_URL + "/api/match/add_point_team",
      method: "POST",
      form: {
        "tenant": localStorage['scoreboard-tenant'],
        "field": field,
        "team": team
      }
    }, function (error, response, body){
      lastPressedTime = Date.now();
      pressedAlreadySent = true;
        
      if (!error && response.statusCode == 200) {
        resolve(body);
      } else {
        log.error("Error request sendPoint:", error, response.statusCode);
        reject(error);
      }
    });
  }).catch((error) => {
    log.error("Error Promise sendPoint:", arguments);
  });
}

function sendBackPoint(field, team, localStorage){
  log.info("sendBackPoint: ", field, team, localStorage);

  return new Promise(function (resolve, reject) {
    request({
      //ca: [certFile],
      rejectUnauthorized: false,
      url: constants.GATEWAY_URL + "/api/match/sub_point_team",
      method: "POST",
      form: {
        "tenant": localStorage['scoreboard-tenant'],
        "field": field,
        "team": team
      }
    }, function (error, response, body){
      lastPressedTime = Date.now();
      pressedAlreadySent = true;
        
      if (!error && response.statusCode == 200) {
        resolve(body);
      } else {
        log.error("Error request sendPoint:", error, response.statusCode);
        reject(error);
      }
    });
  }).catch((error) => {
    log.error("Error Promise sendBackPoint:", arguments);
  });
}

module.exports = { readButtons, getButtonCodes };