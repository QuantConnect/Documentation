# Assigning securities custom fee models:
self.Securities["SPY"].SetFeeModel(CustomFeeModel()) 

# Custom fee implementation
class CustomFeeModel:
    def GetOrderFee(self, security, order):
        return 1