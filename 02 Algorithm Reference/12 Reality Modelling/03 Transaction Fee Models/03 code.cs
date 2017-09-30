// Assigning securities custom fee models:
Securities["SPY"].SetFeeModel(new CustomFeeModel());

// Custom fee implementation
public class CustomFeeModel : IFeeModel {
    public decimal GetOrderFee(Security security, Order order) {
        return 1;
    }
}