$('#uvjet').autocomplete({
    source: function (req, res) {
        $.ajax({
            url: url + 'proizvod/trazi?term=' + req.term + '&narudzba=' + narudzba,
            success: function (odgovor) {
                res(JSON.parse(odgovor));
            }
        });
    },
    minLength: 2,
    select: function (dogadaj, ui) {
        console.log(ui.item),
            spremi(ui.item)
    }
}).autocomplete('instance')._renderItem = function (ul, item) {
    return $('<li>')
        .append('<div>' + item.naziv + ' ' + item.cijena + '</div>')
        .appendTo(ul);
};

function spremi(polaznik) {
    $.ajax({
        url: url + 'narudzba/dodajproizvod?narudzba=' + narudzba + '&proizvod=' + proizvod.sifra,
        success: function (odgovor) {
            $('#podatci').append(
                '<tr>' +
                '<td>' +
                proizvod.naziv + ' ' + proizvod.cijena +
                '</td>' +
                '<td>' +
                '<a class="brisiproizvod" href="#" id="_p' + proizvod.sifra + '">' +
                ' <i style="color: red;" ' +
                'class="step fi-page-delete size-36" ></i>' +
                '</a>' +
                '</td>' +
                '</tr>'
            );
        }
    });
}

function definirajBrisanje() {
    $('.brisiproizvod').click(function () {
        let a = $(this);
        let proizvod = a.attr('id').split('_')[1];
        $.ajax({
            url: url + 'narudzba/obrisiproizvod?narudzba=' + narudzba + '&proizvod=' + proizvod,
            success: function (odgovor) {
                a.parent().parent().remove();
            }
        });

        return false;
    });
}