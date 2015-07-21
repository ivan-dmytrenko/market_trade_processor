messageStatisticApp.activeCurrencyPairId = 0;
messageStatisticApp.chartData = [];

$(document).ready(function(){
    messageStatisticApp.init();

    $('.currency_pair').on('click', function(){
        var currencyPair = $(this),
            currencyPairId = currencyPair.attr('id');
        if (messageStatisticApp.activeCurrencyPairId == currencyPairId) {
            return false;
        }
        messageStatisticApp.activeCurrencyPairId = currencyPairId;
        messageStatisticApp.highlightingControl(currencyPair);
        messageStatisticApp.drawNewChartMap(messageStatisticApp.activeCurrencyPairId);
    });
});

messageStatisticApp.highlightingControl = function(activeCurrencyPair)
{
    $('.currency_pair').removeClass('active');
    activeCurrencyPair.addClass('active');
};

messageStatisticApp.init = function()
{
    this.prepareChartData();

    if (this.data.length === 0) {
        this.drawRegionsMap();

        return false;
    }

    this.prepareHtmlData();
    this.prepareChartData();
    this.setBasicChartData();
    this.drawRegionsMap();
};

messageStatisticApp.drawNewChartMap = function(id)
{
    this.prepareChartData();
    this.getDataForChart(id);
    this.drawRegionsMap();
};

messageStatisticApp.setBasicChartData = function()
{
    this.getDataForChart(0);
};

messageStatisticApp.prepareChartData = function()
{
    this.chartData = [['Country', 'Messages count']];
};

messageStatisticApp.prepareHtmlData = function()
{
    var length = this.data.length;

    for (var i = 0; i < length; i++) {
        item = this.data[i];
        isActive = (i == 0) ? 'active' : '';
        var currencyPairSell = '<span class="sell">'+item['amount_sell']+'</span>';
        var currencyPairBuy = '<span class="buy">'+item['amount_buy']+'</span>';
        var currencyPairFrom = '<div class="from">'+item['currency_from']+currencyPairSell+'</div>';
        var currencyPairTo = '<div class="to">'+item['currency_to']+currencyPairBuy+'</div>';
        var currencyPair = '<li class="currency_pair '+isActive+'" id="'+i+'">'+currencyPairFrom+currencyPairTo+'</li>';
        $('#currencies ul').append(currencyPair);
    }
};

messageStatisticApp.drawRegionsMap = function() {
    var data = google.visualization.arrayToDataTable(this.chartData);

    var options = {};

    var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

    chart.draw(data, options);
};

messageStatisticApp.getDataForChart = function(id)
{
    var countries = this.data[id].country,
        length = countries.length;
    for (var i = 0; i < length; i++) {
        item = countries[i];
        this.chartData.push([item['country'], +item['messages_count']]);
    }
};