# Set IBM to use a fixed $1.5 per trade fee model.
self.Securities["IBM"].FeeModel = ConstantFeeTransactionModel(1.5)

# Set EURUSD to use FXCM's transaction fees:
self.Securities["EURUSD"].FeeModel = new FxcmTransactionModel()