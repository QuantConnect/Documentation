# Set a security to a delayed settlement model: settle 7 days later, at 8am.
self.Securities["IBM"].SettlementModel =  DelayedSettlementModel(7, timedelta(hours = 8))