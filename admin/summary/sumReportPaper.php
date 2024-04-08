<!DOCTYPE html>
<html lang="en">
    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semantic HTML Tables</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin: 20px;
        }

        main,
        aside {
            padding: 10px;
            margin: 10px;
        }
        table{
            border: 1px solid black;
        }

        aside {
            max-width: 25%;
        }
        table thead{
            background-color: #777;
            border: 2px solid #777;
        }
    </style>
</head>

<body>
    <main>
        <table>
            <caption>Main Table</caption>
            <thead>
                <tr>
                    <th>Header 1</th>
                    <th>Header 2</th>
                    <th>Header 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Data 1</td>
                    <td>Data 2</td>
                    <td>Data 3</td>
                </tr>
                <tr>
                    <td>Data 4</td>
                    <td>Data 5</td>
                    <td>Data 6</td>
                </tr>
                <!-- More rows as needed -->
            </tbody>
        </table>
    </main>
    <aside>
        <table>
            <caption>Aside Table 1</caption>
            <thead>
                <tr>
                    <th>Aside Header 1</th>
                    <th>Aside Header 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Aside Data 1</td>
                    <td>Aside Data 2</td>
                </tr>
                <!-- More rows as needed -->
            </tbody>
        </table>
        <table>
            <caption>Aside Table 2</caption>
            <thead>
                <tr>
                    <th>Aside Header 1</th>
                    <th>Aside Header 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Aside Data 1</td>
                    <td>Aside Data 2</td>
                </tr>
                <!-- More rows as needed -->
            </tbody>
        </table>
    </aside>
</body>

</html>