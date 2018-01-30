# Assigning securities custom slippage models:
self.Securities["SPY"].SetSlippageModel(CustomSlippageModel())

# Custom slippage implementation
class CustomSlippageModel:
    def GetSlippageApproximation(self, asset, order):
        return 0.1