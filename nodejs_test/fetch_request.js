import config from "./config.json";
import fetch from "node-fetch"
import fs from "fs";

export class FetchRequest {

    headersList = {
            "Accept": "*/*",
            "User-Agent": "Thunder Client (https://www.thunderclient.com)",
            "Content-Type": "application/json"
        }

    bodyContent = {}
    fileNameToken = "token.txt";

    async doFetch(endPoint, body, method, isIncludeCookie) {
        
      if(isIncludeCookie) await this.includeCookie();
      
      const fetchConfig = { 
        method: method,
        headers: this.headersList
      }

      if(body) {
        this.bodyContent = body;
        fetchConfig.body = JSON.stringify(this.bodyContent)
      }
        
      const response = await fetch(config.url + endPoint, fetchConfig);

      const headers = response.headers;
      const cookieHeader = headers.get('set-cookie');

      if (cookieHeader) {
          await this.saveStringToFile(this.fileNameToken, cookieHeader);
      }

      return response
    }

    async saveStringToFile (fileName, content) {

        fs.writeFile(fileName, content, 'utf-8', (err) => {
            // if (err) {
            //     console.error(`Error writing to file: ${err.message}`);
            // } else {
            //     console.log(`Successfully wrote content to file ${fileName}`);
            // }
        });
    }

    async includeCookie() {
        const token = await this.readFileToString(this.fileNameToken);
        this.headersList.Cookie = token;
    }

    readFileToString(fileName) {
        return new Promise((resolve, reject) => {
          fs.readFile(fileName, 'utf-8', (err, data) => {
            if (err) {
              reject(err); // Reject the promise with the error
            } else {
              resolve(data); // Resolve the promise with the file content
            }
          });
        });
      }

    async loginAdmin() {
        const body = { email: "test@test.com", password: "123456" };
        await this.doFetch("user/login", body, "POST")
    }

    addHeader(headerName, content) {
      this.headersList[headerName] = content
    }
}