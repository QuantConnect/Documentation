# Assigning securities custom fee models:
self.SetFeeModel(CustomFeeModel(), self.Securities["SPY"]) 

# Custom fee implementation
class CustomFeeModel:
    def GetOrderFee(self, security, order):
        return 1