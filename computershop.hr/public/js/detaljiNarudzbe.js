let odabraniProizvod = null;

$('#uvjet').autocomplete({
    source: function (req, res) {
        $.ajax({
            url: url + 'proizvod/trazi?term=' + req.term +
                '&narudzba=' + narudzba,
            success: function (odgovor) {
                res(JSON.parse(odgovor));
                //console.log(odgovor);
            }
        });
    },
    minLength: 2,
    select: function (dogadaj, ui) {
        //console.log(ui.item);
        //spremi(ui.item);
        odabraniProizvod = ui.item;
        $("#kolicinaModal").foundation("open");
        setTimeout(() => {
            $('#kolicina').focus();
        }, 50);

    }
}).autocomplete('instance')._renderItem = function (ul, item) {
    return $('<li>')
        .append('<div>' + item.naziv + ' ' + item.cijena + '<div>')
        .appendTo(ul);
};

function spremi(proizvod) {
    $.ajax({
        url: url + 'narudzba/dodajproizvod?narudzba=' + narudzba +
            '&proizvod=' + proizvod.sifra + '&kolicina=' +
            proizvod.kolicina,
        success: function (odgovor) {
            $('#podatci').append(
                '<tr>' +
                '<td>' +
                proizvod.naziv + ' ' + proizvod.cijena +
                '</td>' +
                '<td>' +
                proizvod.kolicina +
                '</td>' +
                '<td>' +
                ' <a class="brisiProizvod" href="#" id="p_' + proizvod.sifra + '">' +
                ' <i style="color: red;" ' +
                ' class="step fi-page-delete size-36"></i>' +
                '</a>' +
                '</td>' +
                '</tr>'
            );
            $("#kolicinaModal").foundation("close");
            $('#kolicina').val('');
            definirajBrisanje();
        }
    });
}

function definirajBrisanje() {
    $('.brisiProizvod').click(function () {
        let a = $(this);
        let proizvod = a.attr('id').split('_')[1];
        $.ajax({
            url: url + 'narudzba/obrisiproizvod?narudzba=' + narudzba +
                '&proizvod=' + proizvod,
            success: function (odgovor) {
                a.parent().parent().remove();
            }
        });

        return false;
    });
}

definirajBrisanje();
$("#broj_pracenja").focus();

$('#kolicinaOdustani').click(function () {
    $("#kolicinaModal").foundation("close");
    $('#uvjet').focus();
    return false;
});

$('#kolicinaSpremi').click(function () {
    odabraniProizvod.kolicina = $('#kolicina').val();
    spremi(odabraniProizvod);

    return false;
});