// Set IBM to use a fixed $1.5 per trade fee model.
Securities["IBM"].FeeModel = new ConstantFeeTransactionModel(1.5);

// Set EURUSD to use FXCM's transaction fees:
Securities["EURUSD"].FeeModel = new FxcmTransactionModel();