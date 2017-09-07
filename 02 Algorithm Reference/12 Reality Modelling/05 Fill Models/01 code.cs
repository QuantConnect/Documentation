// Set the fill models in initialize:
Securities["IBM"].FillModel = new PartialFillModel();

// Custom fill model implementation stub
public class PartialFillModel : ImmediateFillModel {
    public override OrderEvent MarketFill(Security asset, MarketOrder order) {
        //Override order event handler and return partial order fills, 
    }
}