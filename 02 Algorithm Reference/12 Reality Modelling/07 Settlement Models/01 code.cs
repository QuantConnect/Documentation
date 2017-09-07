// Set a security to a delayed settlement model: settle 7 days later, at 8am.
Securities["IBM"].SettlementModel = new DelayedSettlementModel(7, new TimeSpan(8, 0, 0));