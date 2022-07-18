<html>
    <head>
        <title>Departments</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    
    <body>
        <table id="userTable" border="1" >
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Διεύθυνση</th>
                    <th>Αναγνωριστικό</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script>
        //When the page has loaded.
        $(document).ready(function(){
            $.ajax({
                url: 'https://coding-factory-php.herokuapp.com/department/list',
                type: 'get',
                dataType: 'JSON'
            })
            .done(function(response) {
                // console.log('success ' + JSON.stringify(response));    
                var len = response.length;
                for(var i=0; i<len; i++){
                    var name = response[i].name;
                    var identifier = response[i].identifier;
                        
                    var subdepartment = [];
                    $.each(response[i].subdepartment, function (index, value) {
                        subdepartment.push(value.name);
                    });
                    subdepartment = subdepartment.join(" , ")
                        
                    var tr_str = "<tr>" +
                        "<td align='center'>" + (i+1) + "</td>" +
                        "<td align='center'>" + name + "</td>" +
                        "<td align='center'>" + identifier + "</td>" +
                        "<td align='center'>" + subdepartment + "</td>" +
                        "</tr>";

                    $("#userTable tbody").append(tr_str);
                }    
            });

            // $.getJSON("https://coding-factory-php.herokuapp.com/department/list", function(data){
            //     $.each(data, function (index, value) {
            //         console.log("1>>",value);
            //     });
            // });

            $.ajax({
                url: 'https://coding-factory-php.herokuapp.com/department/list',
                type: 'get',
                dataType: 'JSON'
            })    
            .done(function(response) {
                    $.each (response, function (index, value) {
                        var subdepartment = [];
                        $.each(value.subdepartment, function (index, value) {
                            subdepartment.push(value.name);
                        });
                    subdepartment = subdepartment.join(" , ")
                    console.log("2>>", value.name, value.identifier, subdepartment);
                });
                    
            });

        });

        </script>
    </body>
</html>