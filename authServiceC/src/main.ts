import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import {MicroserviceOptions, Transport} from "@nestjs/microservices";
import {AUTH_SERVICE_PACKAGE_NAME} from "./app/types/authService.pb";
import {globSync} from "glob";

async function bootstrap() {
    const app = await NestFactory.create(AppModule);
    app.connectMicroservice<MicroserviceOptions>({
            transport: Transport.GRPC,
            options: {
                package: AUTH_SERVICE_PACKAGE_NAME,
                protoPath: globSync('src/grpc/protos/**/*.proto', {
                    absolute: true,
                }),
                url: '0.0.0.0:50051',
            },
        },
    );
    await Promise.all([
        await app.startAllMicroservices(),
        await app.listen(3000),
    ]);
}
bootstrap().catch((e) => console.error(e));
