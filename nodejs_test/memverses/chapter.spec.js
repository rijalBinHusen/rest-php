import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Memverses chapter", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("mem_test@test.com", "123456", "memverses/user/login");
    
    const newchapter = {
        id_chapter_client: faker.string.sample({ max: 100 }),
        id_folder: faker.number.int({ max: 12 }) + "",
        chapter: faker.number.int({ max: 112 }),
        verse: faker.number.int({ max: 13 }),
        read_target: faker.number.int({ max: 70 }),
        readed_times: 0
    }

    let idChapterCreated = "";

    it("Should be create new chapter", async () => {

        const response = await fetchReq.doFetch("memverses/chapter", newchapter, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        idChapterCreated = responseJSON.id;
    })

    it("Failed create chapter invalid request body", async () => {

        const body = {
            id_chapter: faker.number.int({ max: 100 }),
            name: faker.string.sample({ max: 12 }),
            total_verse_to_show: faker.number.int({ max: 50 }),
            show_next_chapter_on_second: faker.number.int({ max: 10 }),
            read_target: faker.number.int({ max: 70 }),
            is_show_first_letter: false,
            arabic_size: faker.number.int({ max: 40 })
        }

        const response = await fetchReq.doFetch("memverses/chapter", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add chapter, check the data you sent");

    })

    it("Failed create new chapter because non authenticated", async () => {

        const response = await fetchReq.doFetch("memverses/chapter", newchapter, "POST", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

    it("Should get chapters", async () => {

        const response = await fetchReq.doFetch("memverses/chapters", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data.length).not.equal(0);

    })

    // it("Failed get chapters because non authenticated", async () => {

    //     const response = await fetchReq.doFetch("memverses/chapters", false, "GET")
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(401);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    // })

    // it("Should get chapter by id", async () => {

    //     const response = await fetchReq.doFetch("memverses/chapter/" + idChapterCreated, false, "GET", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.data.name).equal(newchapter.name);
    // })

    // it("Failed get chapter because not authenticated", async () => {

    //     const response = await fetchReq.doFetch("memverses/chapter/" + idChapterCreated, false, "GET")
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(401);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    // })

    // it("chapter not found", async () => {

    //     const response = await fetchReq.doFetch("memverses/chapter/ldldl", false, "GET")
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(404);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("chapter not found");
    // })

    // it("Update readed times should be success", async () => {

    //     const body = { readed_times: 1 }

    //     const response = await fetchReq.doFetch("memverses/chapter/" + idChapterCreated, body, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update chapter success");

    //     const responseGET = await fetchReq.doFetch("memverses/chapter/" + idChapterCreated, false, "GET", true)
    //     const getResponseJSON = await responseGET.json();

    //     expect(responseGET.status).equal(200);
    //     expect(getResponseJSON.success).equal(true);
    //     expect(getResponseJSON.data.readed_times).equal(body.readed_times);

    // })

    // it("Failed update because body invalid", async () => {

    //     const body = { 'failed': "failed test" }

    //     const response = await fetchReq.doFetch("memverses/chapter/" + idChapterCreated, body, "PUT", true)
    //     const responseJSON = await response.json();

    //     console.log(responseJSON)
    //     expect(response.status).equal(400);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("Failed to update chapter, check the data you sent");

    // })

    // it("Failed update unkown chapter", async () => {

    //     const body = { readed_times: 60 }

    //     const response = await fetchReq.doFetch("memverses/chapter/ksdjfh", body, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(404);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("chapter not found");

    // })

})