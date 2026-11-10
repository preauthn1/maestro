import http from "../utils/request";
//successTips需要提示成功时使用
export class TurntableService {
    static async getTurntable() {
        try {
            return await http.get(
              "api.php/user/getTurntable",
            );
        } catch (error) {
            throw error; 
        }
    }
    static async onTurntable(params,callback) {
        try {
            return await http.get(
              "api.php/user/onTurntable",
              {params}
            );
        } catch (error) {
            callback()
            throw error; 
        }
    }
    static async getPddTurntable() {
        try {
            return await http.get(
              "api.php/pdd/getTurntable",
            );
        } catch (error) {
            callback()
            throw error; 
        }
    }
    static async onPddTurntable(params,callback) {
        try {
            return await http.get(
              "api.php/pdd/draw",
              {params}
            );
        } catch (error) {
            callback()
            throw error; 
        }
    }
    static async onPddProgressTurntable(params,callback) {
        try {
            return await http.get(
              "api.php/pdd/updateBigPrizeProgress",
              {params}
            );
        } catch (error) {
            callback()
            throw error; 
        }
    }
    static async onPddReceiveTurntable(params,callback) {
        try {
            return await http.get(
              "api.php/pdd/receiveBigPrize",
              {params}
            );
        } catch (error) {
            callback()
            throw error; 
        }
    }
    static async onPddReceivingRecords(params) {
        try {
            return await http.get(
              "api.php/pdd/receiveRecord",{params}
            );
        } catch (error) {
            throw error; 
        }
    }
    static async getPddDrawRecord(params) {
        try {
            return await http.get(
              "api.php/pdd/drawRecord",{params}
            );
        } catch (error) {
            throw error; 
        }
    }
    static async getPddInvitedRecord(params) {
        try {
            return await http.get(
              "api.php/pdd/invitedRecord",{params}
            );
        } catch (error) {
            throw error; 
        }
    }
     static async financeProductRecord(params) {
        try {
            return await http.get(
              "api.php/pdd/financeProductRecord",{params}
            );
        } catch (error) {
            throw error; 
        }
    }
}