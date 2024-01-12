import { app, BrowserWindow, Tray, Menu } from 'electron';
import * as path from 'path';
import * as log from 'electron-log';
import * as connectArduino from './connect_arduino';
import { createWindow } from './create_window.js';
import * as constants from './constants';
const execFile = require('child_process').execFile;

// Initialize remote module
require('@electron/remote/main').initialize();

let win: BrowserWindow;
let serialPortRegistered = false;
const args = process.argv.slice(1);
const serve = args.some(val => val === '--serve');
let tray: any;

function createTray() {
  tray = new Tray(path.join(__dirname, 'logo.png'));
  
  tray.setContextMenu(Menu.buildFromTemplate([{
      label: 'Padbol Scoreboard',
      enabled: false,
    },{
      type: 'separator',
    },{
      label: 'Show', 
      click: function () {
        win.show();
      }
    },{
      label: 'Quit', 
      click: function () {
        killProcess();
        app.quit();
      }
    }
  ]));
  
}

function killProcess(){
  //TODO-SWITCH-PROD
  const mainPath = path.join(constants.DIST_FOLDER_PATH, 'kill_process.exe');
  //TODO-SWITCH-DEV
  //const mainPath = path.join(__dirname, '/../../../app_recorder/dist/kill_process.exe');

  execFile(mainPath, [], function(err, data) {                    
  });  
}

try {
  // This method will be called when Electron has finished
  // initialization and is ready to create browser windows.
  // Some APIs can only be used after this event occurs.
  // Added 400 ms to fix the black background issue while using transparent window. More detais at https://github.com/electron/electron/issues/15947
  app.on('ready', () => {
    win = createWindow(app, win);
    createTray();
    
    connectArduino.connect(win);
  });

  // Quit when all windows are closed.
  app.on('window-all-closed', () => {
    // On OS X it is common for applications and their menu bar
    // to stay active until the user quits explicitly with Cmd + Q
    log.info("window-all-closed");
    if (process.platform !== 'darwin') {
      app.quit();
    }
  });

  app.on('activate', () => {
    // On OS X it's common to re-create a window in the app when the
    // dock icon is clicked and there are no other windows open.
    if (win === null) {
      win = createWindow(app, win);
    }
  });

  app.on('before-quit', function () {
    if(tray !== undefined)
      tray.destroy();
  });

  // Behaviour on second instance for parent process- Pretty much optional
  app.on('second-instance', (event, argv, cwd) => {
    if (win) {
      if (win.isMinimized()) win.restore()
      win.focus()
    }
  });

} catch (e) {
  log.error(e);
}

//Read serial
/*
setInterval(async () => {
  try{
    if(await serialPortsAvailable(win) && !serialPortRegistered){
      serialPortRegistered = await readButtons(win);
      log.info("Padbol Scoreboard Hub detected");
    }else if(!await serialPortsAvailable(win)){
      serialPortRegistered = false;
      log.info("Padbol Scoreboard Hub not detected");
    }
  } catch(error) {
    log.error("Error:", error);
  }
}, 10000);
*/

/** Check if single instance, if not, simply quit new instance */
let isSingleInstance = app.requestSingleInstanceLock()
if (!isSingleInstance) {
  app.quit();
}