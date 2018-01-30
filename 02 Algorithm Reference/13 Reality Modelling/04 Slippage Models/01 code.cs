// Assigning securities custom slippage models:
Securities["SPY"].SetSlippageModel(new CustomSlippageModel());

// Custom slippage implementation
public class CustomSlippageModel : ISlippageModel {
    public decimal GetSlippageApproximation(Security asset, Order order) {
        return 0.1m;
    }
}