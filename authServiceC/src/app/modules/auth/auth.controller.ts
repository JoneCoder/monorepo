import { Controller } from '@nestjs/common';
import { GrpcMethod } from '@nestjs/microservices';

import * as authServicePb from '../../types/authService.pb';

@Controller()
export class AuthController implements authServicePb.AuthServiceController {

    @GrpcMethod(authServicePb.AUTH_SERVICE_NAME)
    async authCheck(request: authServicePb.AuthRequest): Promise<authServicePb.AuthResponse> {
        console.log('Token received:', request.token);

        // Example validation logic
        if (request.token === 'sjdkghdsfjkhgkjdfhghksdjgklfdsjglkjdfkghjdf') {
            console.log('Valid token received');
            return {
                success: true,
                message: 'Token is valid',
                user: {
                    id: '1',
                    name: 'Shariful',
                    email: 'test@example.com',
                    verifiedAt: new Date().toISOString(),
                },
            };
        }

        return {
            success: false,
            message: 'Invalid token',
            user: undefined,
        };
    }
}