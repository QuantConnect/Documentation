//Security object properties:
class Security {
    Resolution Resolution;
    bool HasData;
    bool Invested;
    DateTime LocalTime;
    SecurityHolding Holdings;
    SecurityExchange Exchange;
    IFeeModel FeeModel;
    IFillModel FillModel;
    ISlippageModel SlippageModel;
    ISecurityPortfolioModel PortfolioModel;
    ISecurityMarginModel MarginModel;
    ISettlementModel SettlementModel;
    IVolatilityModel VolatilityModel;
    ISecurityDataFilter DataFilter;
}