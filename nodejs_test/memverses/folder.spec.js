import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Memverses Folder", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("mem_test@test.com", "123456", "memverses/user/login");
    const newFolder = {
        id_folder: faker.number.int({ max: 100 }) + "",
        name: faker.string.sample({ max: 12 }),
        total_verse_to_show: faker.number.int({ max: 50 }),
        show_next_chapter_on_second: faker.number.int({ max: 10 }),
        read_target: faker.number.int({ max: 70 }),
        is_show_first_letter: false,
        is_show_tafseer: true,
        arabic_size: faker.number.int({ max: 40 })
    }

    let idFolderCreated = "";

    it("Should be create new folder", async () => {

        const response = await fetchReq.doFetch("memverses/folder", newFolder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        idFolderCreated = responseJSON.id;
    })

    it("Failed create folder invalid request body", async () => {

        const body = {
            id_folder: faker.number.int({ max: 100 }),
            name: faker.string.sample({ max: 12 }),
            total_verse_to_show: faker.number.int({ max: 50 }),
            show_next_chapter_on_second: faker.number.int({ max: 10 }),
            read_target: faker.number.int({ max: 70 }),
            is_show_first_letter: false,
            arabic_size: faker.number.int({ max: 40 })
        }

        const response = await fetchReq.doFetch("memverses/folder", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add folder, check the data you sent");

    })

    it("Failed create new folder because non authenticated", async () => {

        const response = await fetchReq.doFetch("memverses/folder", newFolder, "POST", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

    it("Should get folders", async () => {

        const response = await fetchReq.doFetch("memverses/folders", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data.length).not.equal(0);

    })

    it("Failed get folders because non authenticated", async () => {

        const response = await fetchReq.doFetch("memverses/folders", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Should get folder by id", async () => {

        const response = await fetchReq.doFetch("memverses/folder/" + idFolderCreated, false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data.name).equal(newFolder.name);
    })

    it("Failed get folder because not authenticated", async () => {

        const response = await fetchReq.doFetch("memverses/folder/" + idFolderCreated, false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Get folder by Id not found", async () => {

        const response = await fetchReq.doFetch("memverses/folder/ldldl", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Folder not found");
    })

    it("Update folder should be success", async () => {

        const body = { name: "Bismillah" }

        const response = await fetchReq.doFetch("memverses/folder/" + idFolderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update folder success");

        const responseGET = await fetchReq.doFetch("memverses/folder/" + idFolderCreated, false, "GET", true)
        const getResponseJSON = await responseGET.json();

        expect(responseGET.status).equal(200);
        expect(getResponseJSON.success).equal(true);
        expect(getResponseJSON.data.name).equal(body.name);

    })

    it("Failed update because body invalid", async () => {

        const body = { 'failed': "failed test" }

        const response = await fetchReq.doFetch("memverses/folder/" + idFolderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to update folder, check the data you sent");

    })

    it("Failed update unkown folder", async () => {

        const body = { 'name': "Failed test" }

        const response = await fetchReq.doFetch("memverses/folder/skdjhfsdf" + "ksdjfh", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Folder not found");

    })

})