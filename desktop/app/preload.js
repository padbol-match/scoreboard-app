const {
    contextBridge,
    ipcRenderer
} = require("electron");

// Expose protected methods that allow the renderer process to use
// the ipcRenderer without exposing the entire object
contextBridge.exposeInMainWorld(
    "api", {
        request: (channel, data) => {
            ipcRenderer.removeAllListeners(channel)            
            console.info("REGISTER REQUEST", channel, data);
            ipcRenderer.send(channel, data);
        },
        response: (channel, func) => {
            ipcRenderer.removeAllListeners(channel)
            console.info("REGISTER RESPONSE", channel, func, ipcRenderer.rawListeners(channel));
            ipcRenderer.on(channel, (event, ...args) => func(...args));
        }
    }
);
