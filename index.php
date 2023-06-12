<!doctype html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PROJEKTNI XML</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        
        <?php

            $korisnicko_ime="";
            $lozinka="";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                $ans=$_POST;

                if (empty($ans["korisnicko_ime"]))  {
                        echo "Korisnicki račun nije unesen.";
                    
                        }
                else if (empty($ans["lozinka"]))  {
                        echo "Lozinka nije unesena.";
                    
                        }
                else {
                    $korisnicko_ime= $ans["korisnicko_ime"];
                    $lozinka= $ans["lozinka"];
                
                    login($korisnicko_ime,$lozinka);
                }
            }
            function login($korisnicko_ime, $lozinka) {
                

                $xml=simplexml_load_file("korisnici.xml");
                
                
                foreach ($xml->korisnik as $usr) {
                    $usrn = $usr->username;
                    $usrp = $usr->password;
                    $usrime=$usr->ime;
                    $usrprezime=$usr->prezime;
                    if($usrn==$korisnicko_ime){
                        if($usrp == $lozinka){
                            echo "Prijavljeni ste kao $usrime $usrprezime";
                            return;
                            }
                        else{
                            echo "Netocna lozinka";
                            return;
                            }
                        }
                    }
                    
                echo "Korisnik ne postoji.";
                return;
            }
        ?>

        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-primary" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">NBA PRVACI POSLJEDNJIH 5 GODINA</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="container-fluid" style="color:white">
                    <form method="POST" action="">
                        <label for="korisnicko_ime">Korisničko ime:</label>
                        <input type="text" id="korisnicko_ime" name="korisnicko_ime" required>
                        <label for="lozinka">Lozinka:</label>
                        <input type="password" id="lozinka" name="lozinka" required>
                        <input type="submit" value="Potvrdi">
                    </form>
                </div>
        </nav>
            <style>
            .team-page {
            margin: 50px;
            }
            .team-logo {
            width: 30px;
            height: auto;
            margin-left: 10px;
            }
            .pagination-container {
            margin: 20px;
             }
            </style>
    </head>

    <body>

        <div id="teamContainer"></div>

        <div id="teamPageContainer"></div>

        <div class="pagination-container">
            <ul class="pagination" id="teamPagination"></ul>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
            var teamsPerPage = 1; // Broj timova po stranici
            var teamsData; // Svi timovi iz XML dokumenta

            $.ajax({
                type: "GET",
                url: "nbachamps.xml",
                dataType: "xml",
                success: function(xml) {
                teamsData = $(xml).find('champion');
                var totalPages = Math.ceil(teamsData.length / teamsPerPage);

                // Generirajte paginaciju
                for (var i = 1; i <= totalPages; i++) {
                    var pageLink = '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                    $('#teamPagination').append(pageLink);
                }

                // Prikazujte prvu stranicu tima po defaultu
                showTeamPage(1);
                }
            });

            // Funkcija za prikazivanje odabrane stranice tima
            function showTeamPage(page) {
                var startIndex = (page - 1) * teamsPerPage;
                var endIndex = startIndex + teamsPerPage;
                var teamsToShow = teamsData.slice(startIndex, endIndex);

                // Generirajte HTML tablicu za timove
                var table = '<table class="table">';
                table += '<thead><tr><th>Godina</th><th>Tim</th><th>Omjer pobjeda i poraza</th><th>Generalni menadžer</th><th>Trener</th><th>MVP finala</th></tr></thead>';
                table += '<tbody>';

                teamsToShow.each(function() {
                var year = $(this).attr('year');
                var team = $(this).find('team').attr('name');
                var winLossRatio = $(this).find('winLossRatio').text();
                var generalManager = $(this).find('generalManager').text();
                var coach = $(this).find('coach').text();
                var finalsMVP = $(this).find('finalsMVP').text();

                table += '<tr><td>' + year + '</td><td>' + team + '<td>' + winLossRatio + '</td><td>' + generalManager + '</td><td>' + coach + '</td><td>' + finalsMVP + '</td></tr>';
                });

                table += '</tbody>';
                table += '</table>';

                // Prikaži stranicu tima
                $('#teamPageContainer').html(table);
            }

            // Obrada klika na gumb paginacije
            $(document).on('click', '#teamPagination a', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                showTeamPage(page);
            });
            });
        </script>

    </body>

</html>
