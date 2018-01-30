# Set the fill models in initialize:
self.Securities["IBM"].SetFillModel(PartialFillModel())

# Custom fill model implementation stub
class PartialFillModel(ImmediateFillModel):
    def MarketFill(self, asset, order):
        # Override order event handler and return partial order fills
        pass