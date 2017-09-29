# Assigning securities custom slippage models:
self.SetSlippageModel(CustomSlippageModel(), self.Securities["SPY"])

# Custom slippage implementation
class CustomSlippageModel:
    def GetSlippageApproximation(self, asset, order):
        return 0.1