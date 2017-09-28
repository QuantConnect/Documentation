// Assigning securities custom fee models:
Securities["SPY"].FeeModel = new CustomFeeModel()

// Custom fee implementation
public class CustomFeeModel : IFeeModel {
    public decimal GetOrderFee(Security security, Order order) {
        return 1;
    }
}