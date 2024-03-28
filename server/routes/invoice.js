const router = express.Router();
// const { createConnection } = require('mysql2');

// // Create a connection pool
// const conn = new createConnection({
//     user: 'root',
//     host: 'localhost',
//     database: 'shopping',
//     password: '',
//     port: 3306,
// });

// router.post('/createInvoice', async (req, res) => {
//     const { cusID, orderId, tax_id } = req.body;

//     try {
//         const totalAmount = await calculateTotalAmount(orderId);
//         const invoiceId = await createInvoice(cusID, orderId, totalAmount, tax_id);
//         res.status(200).json({ invoiceId });
//     } catch (error) {
//         console.error('Error creating invoice:', error);
//         res.status(500).json({ error: 'Internal server error' });
//     }
// });

// async function createInvoice(cusID, orderId, totalAmount, tax_id) {
//     const client = await pool.connect();
//     try {
//         await client.query('BEGIN');
//         const invoiceInsertQuery = 
//             INSERT INTO invoice (CusID, total_amount, tax_id)
//             VALUES ($1, $2, $3)
//             RETURNING invoice_id
//         ;
//         const { rows } = await client.query(invoiceInsertQuery, [cusID, totalAmount, tax_id]);
//         const invoiceId = rows[0].invoice_id;

//         const invoiceUpdateOrders = 
//             UPDATE orders
//             SET invoice_id = $1
//             WHERE order_id = $2
//         ;
//         await client.query(invoiceUpdateOrders, [invoiceId, orderId]);

//         await client.query('COMMIT');
//         return invoiceId;
//     } catch (error) {
//         await client.query('ROLLBACK');
//         throw error;
//     } finally {
//         client.release();
//     }
// }
// async function calculateTotalAmount(orderId) {
//     let totalAmount = 0;
//     const client = await pool.connect();

//     try {
//         const orderDetailsQuery = 
//             SELECT quantity, subtotal_price
//             FROM order_details
//             WHERE order_id = $1
//         ;
//         const { rows } = await client.query(orderDetailsQuery, [orderId]);

//         rows.forEach(row => {
//             const subtotalPrice = row.subtotal_price;
//             totalAmount += subtotalPrice;
//         });
//         totalAmount = (totalAmount * 0.07) + totalAmount;
//         return totalAmount;
//     } catch (error) {
//         throw error;
//     } finally {
//         client.release();
//     }
// }

// async function insertInvoiceDetails(orderId, invoiceId) {
//     const client = await pool.connect();

//     try {
//         const orderDetailsQuery = 
//             SELECT * FROM order_details WHERE order_id = $1
//         ;
//         const { rows } = await client.query(orderDetailsQuery, [orderId]);

//         rows.forEach(row => {
//             const productId = row.pro_id;
//             const quantity = row.quantity;
//             const pricePerUnit = row.subtotal_price / quantity;
//             const totalPrice = row.subtotal_price;

//             const invoiceDetailInsertQuery = 
//                 INSERT INTO invoice_detail (invoice_id, ProID , quantity, price_per_unit, total_price)
//                 VALUES ($1, $2, $3, $4, $5)
//             ;
//             client.query(invoiceDetailInsertQuery, [invoiceId, productId, quantity, pricePerUnit, totalPrice]);
//         });
//     } catch (error) {
//         throw error;
//     } finally {
//         client.release();
//     }
// }

module.exports = router;