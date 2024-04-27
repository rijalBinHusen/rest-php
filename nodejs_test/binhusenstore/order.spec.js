import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

describe("Binhusenstore order endpoint test", async () => {


    const fetchReq = new FetchRequest();
    await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");

    const newOrder = {
        date_order: faker.date.past().toISOString().slice(0, 10),
        id_group: "",
        is_group: false,
        id_product: faker.string.sample(9),
        name_of_customer: faker.person.firstName(),
        sent: "",
        title: faker.string.sample(13),
        total_balance: faker.number.int({ min: 700000, max: 999999999 }),
        phone: faker.number.int({ min: 6280000000000, max: 6289999999999 }),
        admin_charge: true
    }

    let idOrderCreated = ""

    it("New order should be created", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id
    })

    it("Failed create new order because request body invalid", async () => {

        const body = {
            date_order: "Failed",
            id_group: "Failed",
            is_group: "Failed",
            id_product: "Failed",
            name_of_customer: "Failed",
            sent: "Failed",
            title: "Failed",
            total_balance: "Failed",
            phone: "Failed",
            admin_charge: "Failed"
        }

        const response = await fetchReq.doFetch("binhusenstore/order", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to add order, check the data you sent");

    })

    it("Failed create new order because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");

    })

    it("Should get the order", async () => {

        const response = await fetchReq.doFetch("binhusenstore/orders", false, "GET", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.data[0]).haveOwnProperty("date_order");
        expect(responseJSON.data[0]).haveOwnProperty("id_group");
        expect(responseJSON.data[0]).haveOwnProperty("is_group");
        expect(responseJSON.data[0]).haveOwnProperty("name_of_customer");
        expect(responseJSON.data[0]).haveOwnProperty("sent");
        expect(responseJSON.data[0]).haveOwnProperty("title");
        expect(responseJSON.data[0]).haveOwnProperty("total_balance");
        expect(responseJSON.data[0]).haveOwnProperty("admin_charge");

    })

    it("Failed get order because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/orders", false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Should get order by Id", async () => {
        
        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "GET", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        // expect(responseJSON.data[0].admin_charge).equal(newOrder.admin_charge);
        expect(responseJSON.data[0].date_order).equal(newOrder.date_order);
        expect(responseJSON.data[0].id_group).equal(newOrder.id_group);
        // expect(responseJSON.data[0].is_group).equal(newOrder.is_group);
        expect(responseJSON.data[0].id_product).equal(newOrder.id_product);
        expect(responseJSON.data[0].name_of_customer).equal(newOrder.name_of_customer);
        expect(responseJSON.data[0].sent).equal(newOrder.sent);
        expect(responseJSON.data[0].title).equal(newOrder.title);
        expect(responseJSON.data[0].total_balance).equal(newOrder.total_balance);
    })


    it("Failed get order by id because non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "GET")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })


    it("Order by id not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/aaaa", false, "GET", true, true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Put Order by id should be success", async () => {

        const body = { title: "Updated" }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update order success");
    })

    it("Put Order by id error 400 invalid date order", async () => {

        const body = { date_order:1123123 }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to update order, check the data you sent");
    })

    it("Put Order by id error 401 not authenticated", async () => {

        const body = { title: "sadasdasd" }

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, body, "PUT", false)
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Put Order by id not found", async () => {

        const body = { title: "lskdjfsldkjf" }

        const response = await fetchReq.doFetch("binhusenstore/order/aaaaa", body, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Order should be removed", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "DELETE", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Delete order success");
    })

    it("Remove order non authenticated", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/" + idOrderCreated, false, "DELETE")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Remove order error 404 not found", async () => {

        const response = await fetchReq.doFetch("binhusenstore/order/aaaaa", false, "DELETE", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Move order to archive error 401 non authencticated", async () => {

        const body = {
            id_order: "123456789",
            phone: "0987654321"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST")
        const responseJSON = await response.json();

        expect(response.status).equal(401);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("You must be authenticated to access this resource.");
    })

    it("Move order to archive error 400 invalid phone number", async () => {

        const body = {
            id_order: "123456789",
            phone: "a;sdjfsl;dfskdjfhskdfkj"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(400);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Failed to archive order, check the data you sent");
    })

    it("Move order to archive error 400 invalid id order", async () => {

        const body = {
            id_order: "123456789",
            phone: "0987654321"
        }

        const response = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(404);
        expect(responseJSON.success).equal(false);
        expect(responseJSON.message).equal("Order not found");
    })

    it("Move order to archive error 400 phone number not same", async () => {

        const newOrder = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance: faker.number.int({ min: 700000, max: 999999999 }),
            phone: faker.number.int({ min: 6280000000000, max: 6289999999999 }),
            admin_charge: true
        }

        let idOrderCreated = ""

        const response = await fetchReq.doFetch("binhusenstore/order", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id

        const body = {
            id_order: idOrderCreated,
            phone: 234567245
        }

        const responseMoveToArchive = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseMoveToArchiveJSON = await responseMoveToArchive.json();

        expect(responseMoveToArchive.status).equal(400);
        expect(responseMoveToArchiveJSON.success).equal(false);
        expect(responseMoveToArchiveJSON.message).not.equal("");
    })

    it("Move order to archive success", async () => {

        const start_date = faker.date.past();
        const end_date = new Date(start_date);
        end_date.setDate(start_date.getDate() + 30);

        const total_balance = faker.number.int({ min: 70000, max: 999999 });
        const totalDay = (end_date - start_date) / ( 1000 * 60 * 60 * 24);

        // console.log(`Start date: ${start_date}, end date: ${end_date}, total_balance: ${total_balance}, total day: ${totalDay}, balance_payment: ${total_balance / totalDay}`)

        const newOrder = {
            date_order: faker.date.past().toISOString().slice(0, 10),
            id_group: "",
            is_group: false,
            id_product: faker.string.sample(9),
            name_of_customer: faker.person.firstName(),
            sent: "",
            title: faker.string.sample(13),
            total_balance,
            phone: faker.number.int({ min: 6280000000000, max: 6289999999999 }),
            admin_charge: true,
            start_date_payment: start_date.toISOString().slice(0, 10),
            end_date_payment: end_date.toISOString().slice(0, 10),
            balance_payment: Math.round(total_balance / totalDay),
        }

        let idOrderCreated = ""

        const response = await fetchReq.doFetch("binhusenstore/order_also_payment", newOrder, "POST", true)
        const responseJSON = await response.json();

        expect(response.status).equal(201);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.id).not.equal("");
        idOrderCreated = responseJSON.id

        const body = {
            id_order: idOrderCreated,
            phone: newOrder.phone
        }
        
        const responseMoveToArchive = await fetchReq.doFetch("binhusenstore/order/move_to_archive", body, "POST", true)
        const responseMoveToArchiveJSON = await responseMoveToArchive.json();
        
        expect(responseMoveToArchive.status).equal(201);
        expect(responseMoveToArchiveJSON.success).equal(true);
        expect(responseMoveToArchiveJSON.message).equal("Order archived");
    }, { timeout: 10000})
})