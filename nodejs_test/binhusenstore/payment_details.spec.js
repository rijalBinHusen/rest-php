import { expect } from "chai";
import { describe, expect, it } from "vitest"
import { FetchRequest } from "./fetch_request";
import { faker } from "@faker-js/faker"

const fetchReq = new FetchRequest();
await fetchReq.loginAdmin("binhusen_test@test.com", "123456", "binhusenstore/user/login");

async function createOrder(phone, isGroup) {

    const start_date = faker.date.past();
    const end_date = new Date(start_date);
    end_date.setDate(start_date.getDate() + 30);

    const phone_order = phone || faker.number.int({ min: 6280000000000, max: 6289999999999 });
    const total_balance = faker.number.int({ min: 70000, max: 999999 });
    const totalDay = (end_date - start_date) / (1000 * 60 * 60 * 24);
    // create order 1
    const order = {
        date_order: faker.date.past().toISOString().slice(0, 10),
        id_group: Boolean(isGroup) ? faker.string.sample(9) : "",
        is_group: Boolean(isGroup),
        id_product: faker.string.sample(9),
        name_of_customer: faker.person.firstName(),
        sent: "",
        title: faker.string.sample(13),
        total_balance,
        phone: phone_order,
        admin_charge: true,
        start_date_payment: start_date.toISOString().slice(0, 10),
        end_date_payment: end_date.toISOString().slice(0, 10),
        balance_payment: Math.round(total_balance / totalDay),
    }

    const response = await fetchReq.doFetch("binhusenstore/order/", order, "POST", true)
    // console.log(await response.text());
    const responseJSON = await response.json();
    return {
        id: responseJSON.id,
        phone: phone_order
    }
}


describe("Binhusenstore payment endpoint test", async () => {

    it("Pay more than current bill", async () => {

        const orderInfo = await createOrder()
        const total_balance = faker.number.int({ min: 700, max: 10000 });

        // create payments
        const balancePayment = Math.ceil(total_balance / 3);
        for (let i = 1; i <= 3; i++) {

            const body_to_send = {
                date_payment: "2024-05-0" + i,
                id_order: orderInfo.id,
                balance: balancePayment,
                id_order_group: ""
            }

            const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
            const responseJSON = await responseCreatePayment.json();
            expect(responseCreatePayment.status).equal(201);
            expect(responseJSON.success).equal(true);
        }

        // mark payment as paid
        const firstPaymentTest = balancePayment + 50;
        const data_to_send = {
            id_order: orderInfo.id,
            date_paid: "2024-05-01",
            balance: firstPaymentTest,
            phone: orderInfo.phone
        }

        const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
        const responseJSON = await response.json();

        expect(response.status).equal(200);
        expect(responseJSON.success).equal(true);
        expect(responseJSON.message).equal("Update payment success");

        // get paymment to compare
        const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
        const getPaymentJSON = await getPayment.json();

        // console.log(getPayment.text());
        console.log(getPaymentJSON);
        expect(getPayment.status).equal(200);
        expect(getPaymentJSON.success).equal(true)

        expect(getPaymentJSON.data[0].balance).equal(balancePayment);
        expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
        expect(getPaymentJSON.data[0].is_paid).equal(true);

        expect(getPaymentJSON.data[1].balance).equal(50);
        expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
        expect(getPaymentJSON.data[1].is_paid).equal(true);

        expect(getPaymentJSON.data[2].balance).equal(balancePayment - 50);
        expect(getPaymentJSON.data[2].date_payment).equal("2024-05-02");
        expect(getPaymentJSON.data[2].is_paid).equal(false);

        expect(getPaymentJSON.data[3].balance).equal(balancePayment);
        expect(getPaymentJSON.data[3].date_payment).equal("2024-05-03");
        expect(getPaymentJSON.data[3].is_paid).equal(false);
    })

    // it("Pay less than current bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = 50;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update payment success");

    //     // get paymment to compare
    //     const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
    //     const getPaymentJSON = await getPayment.json();

    //     expect(getPaymentJSON.status).equal(200);
    //     expect(getPaymentJSON.success).equal(true)

    //     expect(getPaymentJSON.data[0].balance).equal(firstPaymentTest);
    //     expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
    //     expect(getPaymentJSON.data[0].is_paid).equal(true);

    //     expect(getPaymentJSON.data[1].balance).equal(balancePayment + 50);
    //     expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[1].is_paid).equal(true);

    //     expect(getPaymentJSON.data[2].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[2].is_paid).equal(false);

    //     expect(getPaymentJSON.data[3].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[3].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[3].is_paid).equal(false);
    // })

    // it("Pay euqal to current bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = balancePayment;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update payment success");

    //     // get paymment to compare
    //     const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
    //     const getPaymentJSON = await getPayment.json();

    //     expect(getPaymentJSON.status).equal(200);
    //     expect(getPaymentJSON.success).equal(true)

    //     expect(getPaymentJSON.data[0].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
    //     expect(getPaymentJSON.data[0].is_paid).equal(true);

    //     expect(getPaymentJSON.data[1].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[1].is_paid).equal(false);

    //     expect(getPaymentJSON.data[2].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[2].is_paid).equal(false);
    // })

    // it("Pay 2 times to current bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = balancePayment * 2;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update payment success");

    //     // get paymment to compare
    //     const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
    //     const getPaymentJSON = await getPayment.json();

    //     expect(getPaymentJSON.status).equal(200);
    //     expect(getPaymentJSON.success).equal(true)

    //     expect(getPaymentJSON.data[0].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
    //     expect(getPaymentJSON.data[0].is_paid).equal(true);

    //     expect(getPaymentJSON.data[1].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[1].is_paid).equal(true);

    //     expect(getPaymentJSON.data[2].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[2].is_paid).equal(false);
    // })

    // it("Pay 2 times and + 50 to current bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = (balancePayment * 2) + 50;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update payment success");

    //     // get paymment to compare
    //     const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
    //     const getPaymentJSON = await getPayment.json();

    //     expect(getPaymentJSON.status).equal(200);
    //     expect(getPaymentJSON.success).equal(true)

    //     expect(getPaymentJSON.data[0].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
    //     expect(getPaymentJSON.data[0].is_paid).equal(true);

    //     expect(getPaymentJSON.data[1].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[1].is_paid).equal(true);

    //     expect(getPaymentJSON.data[2].balance).equal(50);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[2].is_paid).equal(true);

    //     expect(getPaymentJSON.data[2].balance).equal(balancePayment - 50);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[2].is_paid).equal(false);
    // })

    // it("Pay 3 times to current bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = balancePayment * 3;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(200);
    //     expect(responseJSON.success).equal(true);
    //     expect(responseJSON.message).equal("Update payment success");

    //     // get paymment to compare
    //     const getPayment = await fetchReq.doFetch("binhusenstore/payments?id_order=" + orderInfo.id, "", "GET", true, true);
    //     const getPaymentJSON = await getPayment.json();

    //     expect(getPaymentJSON.status).equal(200);
    //     expect(getPaymentJSON.success).equal(true)

    //     expect(getPaymentJSON.data[0].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[0].date_payment).equal("2024-05-01");
    //     expect(getPaymentJSON.data[0].is_paid).equal(true);

    //     expect(getPaymentJSON.data[1].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[1].date_payment).equal("2024-05-02");
    //     expect(getPaymentJSON.data[1].is_paid).equal(true);

    //     expect(getPaymentJSON.data[2].balance).equal(balancePayment);
    //     expect(getPaymentJSON.data[2].date_payment).equal("2024-05-03");
    //     expect(getPaymentJSON.data[2].is_paid).equal(true);
    // })

    // it("Pay more than all bill", async () => {

    //     const orderInfo = await createOrder()
    //     const total_balance = faker.number.int({ min: 700, max: 10000 });

    //     // create payments
    //     const balancePayment = total_balance / 3;
    //     for (let i = 1; i <= 3; i++) {

    //         const body_to_send = {
    //             date_payment: "2024-05-0" + i,
    //             id_order: orderInfo.id,
    //             balance: balancePayment,
    //             id_order_group: ""
    //         }

    //         const responseCreatePayment = await fetchReq.doFetch("binhusenstore/payment", body_to_send, "POST", true);
    //         const responseJSON = await responseCreatePayment.json();
    //         expect(responseCreatePayment.status).equal(201);
    //         expect(responseJSON.success).equal(true);
    //     }

    //     // mark payment as paid
    //     const firstPaymentTest = total_balance + 50;
    //     const data_to_send = {
    //         id_order: orderInfo.id,
    //         date_paid: "2024-05-01",
    //         balance: firstPaymentTest,
    //         phone: orderInfo.phone
    //     }

    //     const response = await fetchReq.doFetch("binhusenstore/payment_mark_as_paid", data_to_send, "PUT", true)
    //     const responseJSON = await response.json();

    //     expect(response.status).equal(400);
    //     expect(responseJSON.success).equal(false);
    //     expect(responseJSON.message).equal("Pembayaran melebihi tagihan");
    // })

})