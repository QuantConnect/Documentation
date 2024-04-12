<li>
    Organize the trade times and prices for each security into a dictionary.
    <div class='section-example-container'>
        <pre class='python'>class OrderData:
    def __init__(self):
        self.buy_fill_times = []
        self.buy_fill_prices = []
        self.sell_fill_times = []
        self.sell_fill_prices = []

order_data_by_symbol = {}
for order in orders:
    if order.symbol not in order_data_by_symbol:
        order_data_by_symbol[order.symbol] = OrderData()
    order_data = order_data_by_symbol[order.symbol]
    is_buy = order.quantity &gt; 0
    (order_data.buy_fill_times if is_buy else order_data.sell_fill_times).append(order.last_fill_time.date())
    (order_data.buy_fill_prices if is_buy else order_data.sell_fill_prices).append(order.price)</pre>
    </div>
</li>

<li>
    Get the price history of each security you traded.
    <div class='section-example-container'>
        <pre class='python'>qb = QuantBook()
start_date = datetime.max.date()
end_date = datetime.min.date()
for symbol, order_data in order_data_by_symbol.items():
    start_date = min(start_date, min(order_data.buy_fill_times), min(order_data.sell_fill_times))
    end_date = max(end_date, max(order_data.buy_fill_times), max(order_data.sell_fill_times))
start_date -= timedelta(days=1)
all_history = qb.history(list(order_data_by_symbol.keys()), start_date, end_date, Resolution.DAILY)</pre>
    </div>
</li>

<li>
    Create a candlestick plot for each security and annotate each plot with buy and sell markers.
  <div class='section-example-container'>
        <pre class='python'>import plotly.express as px
import plotly.graph_objects as go

for symbol, order_data in order_data_by_symbol.items():
    history = all_history.loc[symbol]

    # Plot security price candlesticks
    candlestick = go.Candlestick(x=history.index,
                                open=history['open'],
                                high=history['high'],
                                low=history['low'],
                                close=history['close'],
                                name='Price')
    layout = go.Layout(title=go.layout.Title(text=f'{symbol.value} Trades'),
                    xaxis_title='Date',
                    yaxis_title='Price',
                    xaxis_rangeslider_visible=False,
                    height=600)
    fig = go.Figure(data=[candlestick], layout=layout)

    # Plot buys
    fig.add_trace(go.Scatter(
        x=order_data.buy_fill_times,
        y=order_data.buy_fill_prices,
        marker=go.scatter.Marker(color='aqua', symbol='triangle-up', size=10),
        mode='markers',
        name='Buys',
    ))

    # Plot sells
    fig.add_trace(go.Scatter(
        x=order_data.sell_fill_times,
        y=order_data.sell_fill_prices,
        marker=go.scatter.Marker(color='indigo', symbol='triangle-down', size=10),
        mode='markers',
        name='Sells',
    ))

fig.show()</pre>
    </div>
 </li>

<img src='https://cdn.quantconnect.com/i/tu/plot-backtest-trades-in-research-env-aapl.png' class='docs-image' alt='Plot of AAPL price with buy/sell markers'>
<img src='https://cdn.quantconnect.com/i/tu/plot-backtest-trades-in-research-env-spy.png' class='docs-image' alt='Plot of SPY price with buy/sell markers'>

<p>Note: The preceding plots only show the last fill of each trade. If your trade has partial fills, the plots only display the last fill.</p>